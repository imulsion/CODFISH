from __future__ import print_function
import httplib2
import os
import json

from apiclient import discovery
from oauth2client import client
from oauth2client import tools
from oauth2client.file import Storage

from time import strftime
import datetime

try:
    import argparse
    flags = argparse.ArgumentParser(parents=[tools.argparser]).parse_args()
except ImportError:
    flags = None

# If modifying these scopes, delete your previously saved credentials
# at ~/.credentials/calendar-python-quickstart.json
SCOPES = 'https://www.googleapis.com/auth/calendar.readonly'
CLIENT_SECRET_FILE = 'client_secret.json'
APPLICATION_NAME = 'Google Calendar API Python Quickstart'


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
        print('Storing credentials to ' + credential_path)
    return credentials

def main():
    """Shows basic usage of the Google Calendar API.

    Creates a Google Calendar API service object and outputs a list of the next
    10 events on the user's calendar.
    """
    credentials = get_credentials()
    http = credentials.authorize(httplib2.Http())
    service = discovery.build('calendar', 'v3', http=http)

    # now = datetime.datetime.utcnow().isoformat() + 'Z' # 'Z' indicates UTC time
    # print('Getting the upcoming 15 events')
    # maximum=datetime.datetime(2018, 2, 21, 21, 00, 00, 000000).isoformat() + 'Z'
    # print(maximum)
    events=[]
    outputerino={}
    for var in range(1,8):
        coin=datetime.date.today()+datetime.timedelta(var)
        mdotww=coin.isoformat()
        dotw=datetime.datetime.strptime(mdotww,"%Y-%m-%d").strftime("%A") #This gives the day of the week relative to the current day
        print(dotw + " " + mdotww.split("-",)[2] + " " + mdotww.split("-",)[1] + " " + mdotww.split("-",)[0]) #This will print the date as well as the dotw
        now=mdotww + "T06:00:00Z"
        maximum=mdotww + "T13:00:00Z"
        # print(now)
        # print(maximum)
        # print(var)
        eventsResult = service.events().list(
            calendarId='primary',
            timeMin=now,
            timeMax=maximum,
            maxResults=1,
            singleEvents=True,
            orderBy='startTime').execute()
        # print(type(eventsResult))
        # print(eventsResult)
        events.append(eventsResult.get('items', []))

        if not events[var-1]:
            print('No alarms to set on this day')
            mpmn=24*60
            outputerino[dotw]=mpmn
            print(outputerino)
            print()
        for event in events[var-1]:
            start = event['start'].get('dateTime', event['start'].get('date'))
            # print(start, event['summary'])
            houre = start.split("T")[1].split("Z")[0].split(":")[0]
            minutee = start.split("T")[1].split("Z")[0].split(":")[1]
            seconde = start.split("T")[1].split("Z")[0].split(":")[2]
            alarmsetoutput=datetime.time(int(houre)-1, int(minutee), int(seconde),0) 
            print("Alarm set on " + dotw + " for " + str(alarmsetoutput))
            #The two things to take from this script are the dotw (day of the week) and the alarmsetoutput which hopefully should be enough information I can also return the date as well if need be
            mpmn=(str((int(str(alarmsetoutput).split(":")[0])*60) + int(str(alarmsetoutput).split(":")[1])))
            outputerino[dotw]= mpmn
            print(outputerino)
            print()


# As outputerino already a dictionary which is compatatble 

if __name__ == '__main__':
    main()

#Features to add:
#Adjustable time so the user can enter how many hours before their first event that the alarm goes off
#Adjustable hours for alarm to be set so that the user can determine what hours between the alarms can be set
#robobrowser or selenium so I can log on to sussed and download the student timetable
#return the times ordered by monday to friday with only the hour and minute seperated by colons and commas
#be able to input 2 integers to determine how long before the first event that the alarm can go off
