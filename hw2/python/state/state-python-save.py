#!/usr/bin/env python3

import cgi
import http.cookies
import time

form = cgi.FieldStorage()

name = form.getfirst("name", "").strip()
language = form.getfirst("language", "").strip()
message = form.getfirst("message", "").strip()

expires = time.time() + 3600  # 1 hour
expires_str = time.strftime("%a, %d %b %Y %H:%M:%S GMT", time.gmtime(expires))

cookie = http.cookies.SimpleCookie()
cookie["state_name"] = name
cookie["state_language"] = language
cookie["state_message"] = message

for k in ["state_name", "state_language", "state_message"]:
    cookie[k]["path"] = "/"
    cookie[k]["expires"] = expires_str

print(cookie.output())
print("Status: 302 Found")
print("Location: state-python-view.py")
print("Content-Type: text/html\n")
