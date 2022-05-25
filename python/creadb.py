import sqlite3

con = sqlite3.connect('parole.db')
cur = con.cursor()
cur.execute('''CREATE TABLE parole (parola text, qty integer)''')

con.commit()