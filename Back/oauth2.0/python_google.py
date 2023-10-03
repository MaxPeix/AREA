import flask
import requests
from decouple import config
from flask_cors import CORS
from flask import make_response
import jwt
import datetime

JWT_SECRET = config('JWT_SECRET')

app = flask.Flask(__name__)
CORS(app, supports_credentials=True)

CLIENT_ID = config('CLIENT_ID')
CLIENT_SECRET = config('CLIENT_SECRET')
SCOPE = 'email profile'
REDIRECT_URI = 'http://127.0.0.1:80/api'

@app.route('/')
def index():
  args = flask.request.args
  return oauth2callback(args)

def get_user_info(credentials):
    headers = {'Authorization': 'Bearer {}'.format(credentials['access_token'])}
    req_uri = 'https://openidconnect.googleapis.com/v1/userinfo'
    r = requests.get(req_uri, headers=headers)
    return r.json()

@app.route('/oauth2callback')
def oauth2callback(args=None):
    print('oauth2callback')
    if 'code' not in args:
        auth_uri = ('https://accounts.google.com/o/oauth2/v2/auth?response_type=code'
                    '&client_id={}&redirect_uri={}&scope={}').format(CLIENT_ID, REDIRECT_URI, SCOPE)
        return flask.redirect(auth_uri)
    else:
        auth_code = args.get('code')
        data = {'code': auth_code,
                'client_id': CLIENT_ID,
                'client_secret': CLIENT_SECRET,
                'redirect_uri': REDIRECT_URI,
                'grant_type': 'authorization_code'}
        r = requests.post('https://oauth2.googleapis.com/token', data=data)
        credentials = r.json()

        jwt_payload = {
            'exp': datetime.datetime.utcnow() + datetime.timedelta(hours=1),
            'iat': datetime.datetime.utcnow(),
            'sub': credentials['access_token'],
            'data': get_user_info(credentials)
        }
        jwt_token = jwt.encode(jwt_payload, JWT_SECRET, algorithm='HS256')

        return flask.redirect(f'http://127.0.0.1:80/?jwt={jwt_token}')

@app.route('/test_jwt')
def test_jwt():
    jwt_token = flask.request.headers.get('Authorization')
    if jwt_token is None:
        return make_response('Missing Authorization Header', 401)
    try:
        jwt.decode(jwt_token, JWT_SECRET, algorithms=['HS256'])
        return make_response('OK', 200)
    except jwt.ExpiredSignatureError:
        return make_response('Signature expired. Please log in again.', 401)
    except jwt.InvalidTokenError:
        return make_response('Invalid token. Please log in again.', 401)

if __name__ == '__main__':
  import uuid
  app.secret_key = str(uuid.uuid4())
  # app.secret_key = config('APP_SECRET_KEY')
  app.debug = False
  app.run()
