#!/usr/bin/python

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException

from webdriver_manager.firefox import GeckoDriverManager

import os.path
from os import path
import shutil
import time

HEADERS = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36'}
URL = "https://www.deanza.edu/math/syllabi/archivedsyllabi.html"

def openBrowser():
    driver = webdriver.Firefox(executable_path=GeckoDriverManager().install())
    driver.get(URL)
    timeout = 30
    try:
        WebDriverWait(driver, timeout).until(EC.visibility_of_element_located((By.TAG_NAME, "table")))
        return driver
    except TimeoutException:
        print("Connection timeout")
        driver.quit()
        raise TimeoutException


def parseTable(driver):
    trs = driver.find_elements(By.TAG_NAME, 'tr')
    
    links = dict()

    for tr in trs:
        tds = tr.find_elements(By.TAG_NAME, 'td')
        column = 1
        year = "1000"
        for td in tds:
            if column == 1:
                year = td.text
                if not os.path.exists('data/' + year):
                    os.mkdir('data/' + year)
                links[year] = {}
            else:
                quarter = td.text
                if not os.path.exists('data/' + year + '/' + quarter):
                    os.mkdir('data/' + year + '/' + quarter)
                link = td.find_element(By.TAG_NAME, 'a')
                links[year][quarter] = link.get_attribute('href')
            column += 1
    
    return links


def moveFile(src, dest):
    while not os.path.exists(src):
        time.sleep(1)
    shutil.move(src, dest)

def downloadSyllabi(links):
    mime_types = "application/pdf,application/vnd.adobe.xfdf,application/vnd.fdf,application/vnd.adobe.xdp+xml"

    fp = webdriver.FirefoxProfile()
    fp.set_preference("browser.download.folderList", 2)
    fp.set_preference("browser.download.manager.showWhenStarting", False)
    fp.set_preference("browser.download.dir", "/Users/jay/Desktop/Programs/Python/Scryllabi/data")
    fp.set_preference("browser.helperApps.neverAsk.saveToDisk", mime_types)
    fp.set_preference("plugin.disable_full_page_plugin_for_types", mime_types)
    fp.set_preference("pdfjs.disabled", True)

    browser = webdriver.Firefox(firefox_profile=fp, executable_path=GeckoDriverManager().install())

    for year, linkDict in links.items():
        for quarter, link in linkDict.items():
            browser.get(link)
            syllabi = browser.find_elements(By.LINK_TEXT, 'Syllabus')
            targetFolder = 'data/' + year + '/' + quarter
            for syllabus in syllabi:
                fileName = syllabus.get_attribute('href').split('/')[-1]
                # print(fileName)
                syllabus.click()
                time.sleep(10)
                moveFile('data/' + fileName, targetFolder + '/' + fileName)
            

def scrape():
    Driver = openBrowser()
    links = parseTable(Driver)
    downloadSyllabi(links)

    # print(links)

if __name__ == "__main__":    
    scrape()