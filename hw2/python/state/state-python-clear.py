#!/usr/bin/env python3

import http.cookies

cookie = http.cookies.SimpleCookie()

# Expire cookies in the past
for k in ["state_name", "state_language", "state_message"]:
    cookie[k] = ""
    cookie[k]["path"] = "/"
    cookie[k]["expires"] = "Thu, 01 Jan 1970 00:00:00 GMT"

print(cookie.output())
print("Status: 302 Found")
print("Location: state-python-view.py")
print("Content-Type: text/html\n")

