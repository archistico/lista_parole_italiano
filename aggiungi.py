import sqlite3
con = sqlite3.connect('parole.db')
cur = con.cursor()

def pulisci(value):
    return value.rstrip()

f = open("nuove_parole_non_inserite.txt", mode="r", encoding="utf-8")
for x in f:
    parola = pulisci(x)
    if "'" not in parola and parola!="":
        cur.execute(f'INSERT INTO parole VALUES ("{parola}",0)')
        print(parola)
f.close()

con.commit()