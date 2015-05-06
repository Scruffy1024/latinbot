# LatinBot
First year level Latin conjugation & declension web app

This runs on PHP 5.5 under IIS 7.5 without any PHP extensions.


## How to use
### verb.php
While index.html already does this, you can request verb.php with your own GET parameters.

The order of these does not matter.

#### Pricipal parts
Give verb pricipal parts as `ppx` parameters.
For neco, necare, necavi, necatum:
* pp1=neco&
* pp2=necare&
* pp3=necavi&
* pp4=necatum

#### JSON config
The GET variable `cfg` expects either to not be set or to be set as single line JSON.
JSON variables should be placed at the object's root and not within an array or subobject.
Considered JSON variables are
* noParticipleMode
  Type: int
  Values:
    * `0` Use text character `X` in place of non-existant participles.
    * `1` Use text character &#x2620; in place of non-existant participles.
    * `2` Use tiled SVG image in place of non-existant participles.
* participlePrinciplePartMode
	Type: int
	Values:
	* `0` Use `xus, -a, -um`.
	* `1` Use `xus, xa, xum`.

#### Other GET parameters
If `textonly` is set, the response will be of type `text/plain`, no HTML will be used, and styling will be achieved by drawing 'ASCII art' tables.

#### Examples
```
verb.php?pp1=neco&pp2=necare&pp3=necavi&pp4=necatum
verb.php?pp1=neco&pp2=necare&pp3=necavi&pp4=necatum&cfg={"noParticipleMode":2,"participlePrinciplePartMode":0}
verb.php?pp1=neco&pp2=necare&pp3=necavi&pp4=necatum&textonly=true
verb.php?pp1=neco&pp2=necare&pp3=necavi&pp4=necatum&textonly=
```
