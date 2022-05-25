# -*- coding: utf-8 -*-
import codecs
import locale

print(locale.getpreferredencoding())

f = codecs.open("testi/testo (1).txt", mode="r", encoding="utf-8")
for x in f:
    riga = x.rstrip().encode('utf-8')
    print(riga.decode('cp1252'))
f.close()