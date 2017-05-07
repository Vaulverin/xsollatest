import requests
import time
import string
import random
import os 

dir = os.path.dirname(os.path.abspath(__file__))
root = 'https://xsollatest.dev/'


def genStr():
	return ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(5))

def testResult(name, result=True):
	if result == False:
		print('TEST FAILED: '+name)
		quit()
	print('TEST SUCCESS: '+name)

name = genStr()
password = genStr()
test = 'create-user'
url = root + test

def createUser(url):
	r = requests.post(
		url,
		data={'name': name, 'password': password},
		verify=False)
	return r


r = createUser(url)
if r.ok == False:
	testResult(test, False);
	quit()
testResult(test)
token = r.json()['data']['token']
auth = {'Authorization': token}

test = 'error on create-user existing user'
r = createUser(url)
if r.ok == True:
	testResult(test, False)
testResult(test)

test = 'create file'
url = root + '/file/1.jpg'
def createFile(url, method='post'):
	with open(dir+'/15.jpg', 'rb') as content_file:
		content = content_file.read()
		r = requests.request(method=method, url=url, headers=auth, data=content, verify=False)
	return r

r = createFile(url)
if r.ok == False:
	testResult(test, False)
testResult(test)

test = 'error on creating existing file'
r = createFile(url)
if r.ok == True:
	testRedsult(test, False)
testResult(test)

test = 'update file'
r = createFile(url, 'put')
if r.ok == False:
	testResult(test, False)
testResult(test)

test = 'listing files'
r = requests.get(root+'/list', headers= auth, verify=False)
if r.ok == False:
	testResult(test, False)
testResult(test)

test = 'getting file meta'
r = requests.get(url+'/meta', headers=auth, verify=False)
if r.ok == False:
	testResult(test, False)
testResult(test)

print('ALL TESTS SUCCESSFULLY COMPLETED')
