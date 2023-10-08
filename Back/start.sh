#!/bin/sh

# Lancez votre script Python en arrière-plan
python3 cron.py &

# Continuez avec le lancement de votre application Laravel
php artisan serve --host=0.0.0.0 --port=8000
