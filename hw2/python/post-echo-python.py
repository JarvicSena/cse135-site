#!/usr/bin/env python3

import cgi
import html
import os
import sys

try:
    content_length = int(os.environ.get("CONTENT_LENGTH", "0"))
except ValueError:
    content_length = 0

raw_body = sys.stdin.read(content_length) if content_length > 0 else ""

form = cgi.FieldStorage()

print("Content-Type: text/html\n")

print("<!DOCTYPE html>")
print("<html>")
print("<head><title>Post Request Echo</title></head>")
print('<body><h1 align="center">Post Request Echo</h1>')
print("<hr>")

print(f"<p><b>Message Body:</b> {html.escape(raw_body)}</p>")

if len(form) > 0:
    for key in form.keys():
        value = form.getfirst(key, "")
        print(f"<p>{html.escape(key)} = {html.escape(value)}</p>")
else:
    print("<p>No POST parameters received.</p>")

print("</body>")
print("</html>")
