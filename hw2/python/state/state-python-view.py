#!/usr/bin/env python3

import os
import html
import http.cookies

raw_cookie = os.environ.get("HTTP_COOKIE", "")
cookie = http.cookies.SimpleCookie()
cookie.load(raw_cookie)

def get_cookie(name: str) -> str:
    return cookie[name].value if name in cookie else ""

name = get_cookie("state_name")
language = get_cookie("state_language")
message = get_cookie("state_message")

print("Content-Type: text/html\n")

print("""<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>State Demo - View (Cookies)</title>
</head>
<body>
  <h1 align="center">State Demo (Cookies) - View Saved Data</h1>
  <hr>
""")

if name == "" and language == "" and message == "":
    print("<p><i>No saved state found.</i></p>")
else:
    print(f"<p><b>Name:</b> {html.escape(name)}</p>")
    print(f"<p><b>Favorite Language:</b> {html.escape(language)}</p>")
    print(f"<p><b>Message:</b> {html.escape(message)}</p>")

print("""
  <hr>

  <form method="POST" action="state-python-clear.py">
    <button type="submit">Clear Saved State</button>
  </form>

  <p><a href="state-python-form.py">Back to Form</a></p>
</body>
</html>
""")
