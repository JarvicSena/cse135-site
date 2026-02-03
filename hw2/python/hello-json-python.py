#!/usr/bin/env python3

import json
import datetime
import os

now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

ip = os.environ.get("REMOTE_ADDR", "Unknown")

response = {
    "greeting": "Hello from Richard and James",
    "language": "This program was generated with Python",
    "date": "This program was generated at " + now,
    "ip": "Your current IP address is: " + ip
}

print("Content-Type: application/json\n")

print(json.dumps(response))
