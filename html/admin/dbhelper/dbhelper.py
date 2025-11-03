from flask import Flask, g
from lib import RDB
import datetime

app = Flask(__name__)

def _get_connection():
    if not g.get('db_conn'):
        g.db_conn = RDB.CreateConnection()
    return g.db_conn

def _QueryWithFetchAll(query, args=None):
    return RDB.QueryWithFetchAll(_get_connection(), query, args)

def _QueryWithCommit(query, args=None):
    return RDB.QueryWithCommit(_get_connection(), query, args)

def _QueryWithCommit_GetRowCount(query, args=None):
    return RDB.QueryWithCommit_GetRowCount(_get_connection(), query, args)

def _InsertQuery_GetLastRowId(query, args=None):
    return RDB.InsertQuery_GetLastRowId(_get_connection(), query, args)

def close_connection():
    if g.get('db_conn'):
        g.db_conn.close()
        g.db_conn = None

@app.after_request
def after_request(response):
    close_connection()
    return response