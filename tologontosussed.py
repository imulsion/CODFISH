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

driver = webdriver.Chrome()


# Load selenium
semcheck=datetime.date.today()
# January 22nd is roughly when the switch over happens
if (semcheck.day>>21 and semcheck.month==1) or 7>>semcheck.month>>1:
    driver.get("https://timetable.soton.ac.uk/Home/Semester/2/")
elif(6<<semcheck.month<<9):
    driver.quit()
else:
    driver.get("https://timetable.soton.ac.uk/Home/Semester/1/")


# Wait for the page to load
element = WebDriverWait(driver, 10).until(
         # Wait until a button is on the screen
        EC.presence_of_element_located((By.ID, "userNameInput"))
)

#Look for id userNameInput
#Fill in user name
inputUsername = driver.find_element_by_id("userNameInput")
inputUsername.send_keys("rrm1g16") 

#Look for id passwordInput
#Fill in password
inputPassword = driver.find_element_by_id("passwordInput")
inputPassword.send_keys("ThisIsNotMyPassword")
#Press id submitButton
driver.find_element_by_id("submitButton").click()

element = WebDriverWait(driver, 10).until(
    #Waits for the calendar to actually load
    EC.presence_of_element_located(By.ID, "calendar")
)

# for row in lines:
#     sleep(1)
#     # Click 'new' then wait for the form to appear
#     driver.find_element_by_id('newItem').click()
#     element = WebDriverWait(driver, 120).until(
#             EC.presence_of_element_located((By.ID, "Quantity"))
#     )
#     sleep(3)
#     # Fill out all the values in the form
#     quant = driver.find_element_by_id('Quantity')
#     quant.clear() # remove autofilled text
#     quant.send_keys(row['Quantity'])
#     sleep(3)

#     driver.find_element_by_id('Stockcode').send_keys(row['Code']+'\t')
#     sleep(1)

#     driver.find_element_by_id('Description1').send_keys(row['Link']+'\t')
    
#     driver.find_element_by_id('PRODUCT_CODE').send_keys('M1017'+Keys.RETURN+'\t')

#     account_code = driver.find_element_by_id('Account_Code')
#     account_code.clear() # remove autofilled text
#     account_code.send_keys('501345101'+'\t')

#     price = driver.find_element_by_id('UnitPrice')
#     price.clear()
#     price.send_keys(row['Per Item'][1:]+'\t')

#     driver.find_element_by_id('SpecialItemInstructions').send_keys('The item is for GDP team 2\t')

#     driver.find_element_by_id('LOCATION').send_keys('16/1001\t')
#     sleep(1)

#     # Use xpath to find this element because it doesn't have a nice ID
#     driver.find_element_by_xpath('/html/body/div[2]/div[11]/div/button[1]').click()
#     element = WebDriverWait(driver, 120).until(
#             # Wait for the form to be gone
#             EC.invisibility_of_element_located((By.ID, "Quantity"))
#     )
print("Done!")
driver.quit()
