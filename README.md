# Tietokantasovelluksen esittelysivu

Yleisiä linkkejä:

* [Sovellus](http://brute.havoc.fi:8800/tsoha/)
* [Dokumentaatio](https://github.com/bgran/Tsoha-Bootstrap/blob/master/doc/dokumentaatio.pdf)

## Tietokantasovelluksen kaytto

Jarjestelmassa on anon ja admin -henkiloita. Admin voi tehda useampi asioita,
kuten lisata staattisia pizzoja, ja lisata pizza-lisukkeita. Kaikki muut
kayttajat ovat anon -kayttajia. Admin-tunnukselle kirjaudutaan etusivn
linkin kauttta. Kayttajatunns on "admin" ja salasana "foobar".

## Sovelluskayttoliittyma

Sovelluksen ulkonako paivittyy jatkuvasti osoitteessa [http://brute.havoc.fi:8800/tsoha/](http://brute.havoc.fi:8800/tsoha/)
eli erillisia ulkonako-testi-sivuja ei tehda. Sovellus elaa ketterassa mallissa, ja se paivittyy nopeasti kun
tehdaan muutoksia koodiin.

## Tietokanta

Sovellus kayttaa liikkuvaa maalia SQL-tietokannan osalta. Sovelluksen tietokantaesitys muuttuu
suunnittelun toimesta jatkuvasti. create_tables.sh ja add_test_data.sh:ta ajetaan jatkuvasti
tietokantaan, joten sql/ -hakemiston tiedostot ovat kokoajan paivitettyina.

## Työn aihe

[Pizza-palvelu IS97HL5](http://advancedkittenry.github.io/suunnittelu_ja_tyoymparisto/aiheet/Pizzapalvelu.html) 
