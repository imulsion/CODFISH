#!/usr/bin/env python3
from lxml import etree
import unicodedata
import uuid
import time
import os
import json
from apiclient import discovery
from oauth2client import client
from oauth2client import tools
from oauth2client.file import Storage
import httplib2
import requests

import time
import datetime as dt
from datetime import datetime, timedelta

try:
    input = raw_input  # for Python 2 compatibility
except NameError:
    pass

try:
    import argparse
    flags = argparse.ArgumentParser(parents=[tools.argparser]).parse_args()
except ImportError:
    flags = None


SCOPES = 'https://www.googleapis.com/auth/calendar'
CLIENT_SECRET_FILE = 'client_secret.json'
APPLICATION_NAME = 'Google Calendar API Python Quickstart'


MONDAY_OF_FIRST_WEEK = "2017/10/02"  # YYYY/MM/DD

# Customise what the calendar entries look like.

TITLE_FORMAT = "{name} ({code})"
LOCATION_FORMAT = "{location}"
DESCRIPTION_FORMAT = "{code}"

VERSION = 0.2

# convert it to datetime
MONDAY_OF_FIRST_WEEK_DATETIME = datetime.strptime(
        MONDAY_OF_FIRST_WEEK,
        "%Y/%m/%d"
    )

# Day -> Int Dictionary
DAY_OFFSETS = {
    "Mon": 0,
    "Tue": 1,
    "Wed": 2,
    "Thu": 3,
    "Fri": 4,
    "Sat": 5,
    "Sun": 6,
}

# Day -> ISO Dictionary
DAY_TO_ISO = {
    "Mon": "MO",
    "Tue": "TU",
    "Wed": "WE",
    "Thu": "TH",
    "Fri": "FR",
    "Sat": "SA",
    "Sun": "SU",
}
def get_credentials():
    """Gets valid user credentials from storage.
    If nothing has been stored, or if the stored credentials are invalid,
    the OAuth2 flow is completed to obtain the new credentials.

    Returns:
        Credentials, the obtained credential.
    """
    home_dir = os.path.expanduser('~')
    credential_dir = os.path.join(home_dir, '.credentials')
    if not os.path.exists(credential_dir):
        os.makedirs(credential_dir)
    credential_path = os.path.join(credential_dir,
                                   'calendar-python-quickstart.json')

    store = Storage(credential_path)
    credentials = store.get()
    if not credentials or credentials.invalid:
        flow = client.flow_from_clientsecrets(CLIENT_SECRET_FILE, SCOPES)
        flow.user_agent = APPLICATION_NAME
        if flags:
            credentials = tools.run_flow(flow, store, flags)
        else: # Needed only for compatibility with Python 2.6
            credentials = tools.run(flow, store)
        #print('Storing credentials to ' + credential_path)
    return credentials

credentials = get_credentials()
http = credentials.authorize(httplib2.Http())
service = discovery.build('calendar', 'v3', http=http)

def get_term_weeks_from_string(week_string):
    weeks = []
    # Last column is the weeks they are on.
    # Example: "1-11, 15"
    week_specs = week_string.split(",")
    for week_spec in week_specs:
        # If it's a range of weeks
        if "-" in week_spec:
            # i.e. 1-10
            ind_weeks = week_spec.split("-")
            start_week_int = int(ind_weeks[0])
            end_week_int = int(ind_weeks[1])
            for i in range(start_week_int, end_week_int+1):
                weeks.append(i)
        else:
            try:
                individual_week = int(week_spec)
            except ValueError:
                raise Exception("Suspected Malformed timetable")
            weeks.append(individual_week)
    return weeks

def clean(dirty_string):
    return dirty_string.strip()

def scrape_page(page_string):
    page = etree.HTML(page_string)
    table = page.xpath("//*[@id=\"calendarTable\"]/table/tbody")[0]
    lectures = []
    for row in table:
        lecture = {}
        lecture["code"] = clean(row[0][0].text)
        lecture["name"] = clean(row[1].text)
        # for some reason the lecture string has a unicode character on the end
        # We're clearing it here.
        lecture["type"] = clean(row[2].text)
        term_weeks = get_term_weeks_from_string(clean(row[6].text))

        # if there aren't any weeks, stop.
        if not term_weeks:
            raise Exception("Timetable entry has 0 valid weeks!")

        # get the weeks in ISO week form
        weeks = get_ISO_weeks(
                term_weeks,
                MONDAY_OF_FIRST_WEEK_DATETIME
            )


        # get the weekday name
        day_string = clean(row[3].text)
        # get the start and end times in HH:MM and convert them to datetime
        start_time_hhmm = clean(row[4].text)
        end_time_hhmm = clean(row[5].text)

        # Get a tuple of the start and end datetimes of the first lecture.
        start_and_end_datetime = get_datetime_of_lecture(
            start_time_hhmm,
            end_time_hhmm,
            day_string,
            term_weeks[0]
            )
        #recursions = [start_and_end_datetime[0] + timedelta(weeks=week-term_weeks[0]) for week in term_weeks]
        lecture["day"] = day_string
        lecture["start_time"] = start_and_end_datetime[0]
        lecture["end_time"] = start_and_end_datetime[1]
        #lecture["recursions"] = recursions
        lecture["weeks"] = weeks
        lecture["week_times"] = weeks
        lecture["location"] = row[7].text.encode('utf-8').strip()

        lectures.append(lecture)

    # print (etree.tostring(table, pretty_print=True))
    return lectures


def day_to_iso_day(day_string):
    return DAY_TO_ISO[day_string]


def get_ISO_weeks(term_weeks, first_day_of_term):
    week_offsets = [get_week_offset(x) for x in term_weeks]
    return [
        (first_day_of_term+x).isocalendar()[1]
        for x in week_offsets
    ]

def get_day_offset(day_string):
    if day_string in DAY_OFFSETS:
        day_offset = DAY_OFFSETS[day_string]
    else:
        raise Exception("Badly formatted weekday! {}".format(day_string))
    return timedelta(days=day_offset)


def get_week_offset(week_number):
    return timedelta(weeks=week_number-1)

def get_week_spans(weeks):
    spans = []
    minim = weeks[0]
    maxim = weeks[0]
    for w in weeks:
        if w == (maxim+1) or w == weeks[0]:
            maxim = w
        else:
            spans.append((minim, maxim))
            minim = w
            maxim = w
    spans.append((minim,maxim))
    return spans

def get_time_offset(time):
    t = datetime.strptime(time, "%H:%M")
    return timedelta(hours=t.hour, minutes=t.minute, seconds=t.second)


def get_datetime_of_lecture(
    start_time_string,
    end_time_string,
    day_string,
    first_week_number
  ):
    """
    returns UTC datetime of the start and end times.
    """
    day_delta = get_day_offset(day_string)
    week_delta = get_week_offset(first_week_number)
    day_date = MONDAY_OF_FIRST_WEEK_DATETIME + week_delta + day_delta
    start_hour_delta = get_time_offset(start_time_string)
    end_hour_delta = get_time_offset(end_time_string)
    start_time = day_date+start_hour_delta
    end_time = day_date+end_hour_delta

    return (start_time, end_time)


def user_interface():

    url = "https://timetable.soton.ac.uk/Home/Semester/<semester_number>/ (1 or 2)"
    #print("This code is configured for the year starting: {}".format(MONDAY_OF_FIRST_WEEK))

    while True:
        if os.path.isfile("My Timetable.html"):
            break
        else:
            #print("...")

    with open("My Timetable.html") as f:
        #Scrapes the html to make it easier to deal with
        lectures = scrape_page(f.read())
        for lecture in lectures:
            start=lecture["start_time"].strftime("%Y-%m-%dT%H:%M:%SZ")
            end=lecture["end_time"].strftime("%Y-%m-%dT%H:%M:%SZ")
            name=lecture["name"]
            #RDATES became too awkward to upload so I just replaced them with a rule until the end of the semster. This is a bad fix but Ill work on it if I have time
            '''
            rdates="".join(["RDATE:{} ".format(recursion.strftime("%Y-%m-%d-T%H:%M:%SZ"))
            for recursion in lecture["recursions"]
            ]),
            '''
            loc=str(lecture["location"].replace(b"\n", b""))
            #Puts all the data in the event
            event={
                "summary": name,
                "location": loc,
                "start":{
                    "dateTime":start,
                    "timeZone":"Etc/GMT",
                },
                "end":{
                    "dateTime":end,
                    "timeZone":"Etc/GMT",
                },
                "recurrence":[
                    "RRULE:FREQ=WEEKLY;UNTIL=20180520"
                ],
            }
            json_event = json.loads(json.dumps(event))
            #Uploads the event to the Student Timetable calendar
            page_token=None
            calendar_list = service.calendarList().list(pageToken=page_token).execute()
            for calendar_list_entry in calendar_list['items']:
                if calendar_list_entry['summary']=="Student Timetable":
                    page_token = calendar_list.get('nextPageToken')
                    event=service.events().insert(calendarId=calendar_list_entry["id"], body=json_event).execute()

def test():
    with open("My Timetable.html") as f:
        lectures = scrape_page(f.read())
    #print(export_as_ical(lectures))

def main():
    #Creates a new calendar for the timetable to go in unless it already exists
    page_token=None
    tag=0
    calendar_list = service.calendarList().list(pageToken=page_token).execute()
    for calendar_list_entry in calendar_list['items']:
        #print(calendar_list_entry['summary'])
        if calendar_list_entry['summary']=="Student Timetable":
            page_token = calendar_list.get('nextPageToken')
            tag=1
        else:
            page_token = calendar_list.get('nextPageToken')

    if tag==0:
        #print("New Student Timetable Created")
        calendar={
            'summary':'Student Timetable'
        }
        service.calendars().insert(body=calendar).execute()
        user_interface()

    page_token=None
    calendar_list = service.calendarList().list(pageToken=page_token).execute()
    for calendar_list_entry in calendar_list['items']:
        if calendar_list_entry['summary']=="Student Timetable":
            wcalendar=calendar_list_entry['id']
            events=[]
            output={}
            for var in range(1,8):
                a=dt.date.today()+dt.timedelta(var)
                mdotww=a.isoformat()
                dotw=dt.datetime.strptime(mdotww,"%Y-%m-%d").strftime("%A") #This gives the next of occurance of a particular DOTW
                #print(dotw + " " + mdotww.split("-",)[2] + " " + mdotww.split("-",)[1] + " " + mdotww.split("-",)[0]) #This will print the date as well as the dotw
                now=mdotww + "T05:00:00Z"
                maximum=mdotww + "T14:00:00Z"
                
                try:
                    eventsResult = service.events().list(
                        calendarId=wcalendar,
                        timeMin=now,
                        timeMax=maximum,
                        maxResults=1,
                        singleEvents=True,
                        orderBy='startTime').execute()
                    events.append(eventsResult.get('items', []))
                    if not events[var-1]:
                        #print('No alarms to set on this day')
                        mpmn=1440
                        output[dotw]=str(mpmn)
                        requests.get("http://linuxproj.ecs.soton.ac.uk/~sk6g16/json_get.php?data="+dotw+str(output[dotw]))
                        #print(output)
                        #print()
                    for event in events[var-1]:
                        start = event['start'].get('dateTime', event['start'].get('date'))
                        houre = start.split("T")[1].split("Z")[0].split(":")[0]
                        minutee = start.split("T")[1].split("Z")[0].split(":")[1]
                        seconde = start.split("T")[1].split("Z")[0].split(":")[2]
                        alarmsetoutput=dt.time(int(houre), int(minutee), int(seconde),0) 
                        #print("Alarm set on " + dotw + " for " + str(alarmsetoutput))
                        #The two things to take from this script are the dotw (day of the week) and the alarmsetoutput which hopefully should be enough information I can also return the date as well if need be
                        mpmn=(str((int(str(alarmsetoutput).split(":")[0])*60) + int(str(alarmsetoutput).split(":")[1])))
                        output[dotw]= strmpmn
                        requests.get("http://linuxproj.ecs.soton.ac.uk/~sk6g16/json_get.php?data="+dotw+str(output[dotw]))
                        #print(output)
                        #print()
                except:
                    #print("Your Timetable is empty")
                    output[dotw]=1440
                    requests.get("http://linuxproj.ecs.soton.ac.uk/~sk6g16/json_get.php?data="+dotw+str(output[dotw]))
                    #print(output)
                    #print()
            print(output)
            #print("Done!")
            break
        else:
            page_token = calendar_list.get('nextPageToken')

if __name__ == "__main__":
    main()
