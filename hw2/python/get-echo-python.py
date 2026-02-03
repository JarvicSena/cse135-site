#!/usr/bin/env python3

import cgi
import html
import os

qs = os.environ.get("QUERY_STRING", "")

print("Content-Type: text/html\n")

print("<!DOCTYPE html>")
print("<html>")
print("<head><title>GET Request Echo</title></head>")
print('<body><h1 align="center">Get Request Echo</h1>')
print("<hr>")

print(f"<p><b>Query String: </b>{html.escape(qs)}</p>")

form = cgi.FieldStorage()

if len(form) > 0:
    for key in form.keys():
        value = form.getfirst(key, "")
        print(f"<p>{html.escape(key)} = {html.escape(value)}</p>")
else:
    print("<p>No GET parameters received.</p>")

print("</body>")
print("</html>")
