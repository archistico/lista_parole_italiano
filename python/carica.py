import sqlite3
con = sqlite3.connect('parole.db')
cur = con.cursor()

def pulisci(value):
    return value.rstrip()

f = open("parole_uniche.txt", mode="r", encoding="utf-8")
for x in f:
    parola = pulisci(x)
    if "'" not in parola:
        cur.execute(f'INSERT INTO parole VALUES ("{parola}",0)')
        print(parola)
f.close()

con.commit()