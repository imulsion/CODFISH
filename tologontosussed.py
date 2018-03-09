#!/usr/bin/env python
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from time import sleep
import datetime
from csv import reader
import pickle
import requests
import sys
import timetable_converter
from apiclient import discovery
from oauth2client import client
from oauth2client import tools
from oauth2client.file import Storage
import os
import httplib2

def main():

    driver = webdriver.Chrome()

    output={}
    # Load selenium
    semcheck=datetime.date.today()
    # January 22nd is roughly when the switch over happens
    if (int(semcheck.day)>21 and int(semcheck.month)==1) or 7>int(semcheck.month)>1:
        driver.get("https://timetable.soton.ac.uk/Home/Semester/2/")
    elif(6<int(semcheck.month)<9):
        #driver.quit()
        #print("It's the summer you don't need a timetable")
        for var in range(1,8):
            a=datetime.date.today()+datetime.timedelta(var)
            mdotww=a.isoformat()
            dotw=datetime.datetime.strptime(mdotww,"%Y-%m-%d").strftime("%A")
            output[dotw]=1440
            resp = requests.get("http://linuxproj.ecs.soton.ac.uk/~sk6g16/json_get.php?data="+str(output))
        sys.exit()
    else:
        driver.get("https://timetable.soton.ac.uk/Home/Semester/1/")


    # Wait for the page to load
    element = WebDriverWait(driver, 10).until(
             # Wait until a button is on the screen
            EC.presence_of_element_located((By.ID, "userNameInput"))
    )

    #Look for id userNameInput
    #Fill in user name
    with open("notongit.txt",'r') as file:
        alllines=file.readlines()
        username=alllines[0]
        password=alllines[1]

    inputUsername = driver.find_element_by_id("userNameInput")
    inputUsername.send_keys(username) 

    sleep(0.5)

    #Look for id passwordInput
    #Fill in password
    inputPassword = driver.find_element_by_id("passwordInput")
    inputPassword.send_keys(password)
    #Press id submitButton
    driver.find_element_by_id("submitButton").click()

    element = WebDriverWait(driver, 10).until(
        #Waits for the calendar to actually load
        EC.presence_of_element_located((By.ID, "calendar"))
    )
    #Finds the HTML of the SUSSED webpage and puts it in the drive
    elem = driver.find_element_by_xpath("//*")
    source_code = elem.get_attribute("outerHTML")
    source_code=source_code.replace("-3--4","1-11, 15, 18-24, 29-33")#Gets rid of an issue in the timetable
    with open('My Timetable.html','w') as f:
        f.write(source_code)

    #driver.quit()
    #timetable_converter.main() #Converts the downloaded sussed timetable into ics

    #print("Done!!")
if __name__ == '__main__':
    main()

# Take 1 lecture turn it into an event then return it and loop for number of lectures