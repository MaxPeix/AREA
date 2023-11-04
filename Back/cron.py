# exec an action every 5 minutes
from time import sleep
import os

while (True):
    os.system("php artisan schedule:run")
    sleep(30)
