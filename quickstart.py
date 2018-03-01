'''
Things that have to be installed:
Google Calendar API
Requests
Selenium IF the log on to sussed code works
Chrome web driver
lmxl
'''

from __future__ import print_function
import httplib2
import os
import json
import requests

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
SCOPES = 'https://www.googleapis.com/auth/calendar'
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
    credentials = get_credentials()
    http = credentials.authorize(httplib2.Http())
    service = discovery.build('calendar', 'v3', http=http)

    '''What I want to recieve:
    Name of calendar
    Additional feature: Whether it is nightmode or day mode
    '''
    #testout=requests.post("http://linuxproj.ecs.soton.ac.uk/~sk6g16/json_get.php","Hello")
    processweb=requests.get("http://linuxproj.ecs.soton.ac.uk/~sk6g16/json_get.php")
    print(processweb.status_code)
    print(processweb.text)
    wcalendar=processweb.text
    if 0==len(wcalendar):
        wcalendar='primary'
    events=[]
    output={}
    for var in range(1,8):
        a=datetime.date.today()+datetime.timedelta(var)
        mdotww=a.isoformat()
        dotw=datetime.datetime.strptime(mdotww,"%Y-%m-%d").strftime("%A") #This gives the next of occurance of a particular DOTW
        print(dotw + " " + mdotww.split("-",)[2] + " " + mdotww.split("-",)[1] + " " + mdotww.split("-",)[0]) #This will print the date as well as the dotw
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
                print('No alarms to set on this day')
                mpmn=1440
                output[dotw]=mpmn
                print(output)
                print()
            for event in events[var-1]:
                start = event['start'].get('dateTime', event['start'].get('date'))
                houre = start.split("T")[1].split("Z")[0].split(":")[0]
                minutee = start.split("T")[1].split("Z")[0].split(":")[1]
                seconde = start.split("T")[1].split("Z")[0].split(":")[2]
                alarmsetoutput=datetime.time(int(houre), int(minutee), int(seconde),0) 
                print("Alarm set on " + dotw + " for " + str(alarmsetoutput))
                #The two things to take from this script are the dotw (day of the week) and the alarmsetoutput which hopefully should be enough information I can also return the date as well if need be
                mpmn=(str((int(str(alarmsetoutput).split(":")[0])*60) + int(str(alarmsetoutput).split(":")[1])))
                output[dotw]= mpmn
                print(output)
                print()
        except:
            print("Your calendar could not be found")
            output[dotw]=1440
    testout=requests.post("http://linuxproj.ecs.soton.ac.uk/~sk6g16/json_get.php",data=output)
    print(testout.text)
# As output is already a dictionary which is compatatble with JSON

if __name__ == '__main__':
    main()

'''Features to add:
Adjustable hours for alarm to be set so that the user can determine what hours between the alarms can be set
selenium so I can log on to sussed and download the student timetable
JSON won't work because isolutions suck therefore use POST and urllib
'''
