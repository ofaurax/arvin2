# arvin2

A script to search in HMAP (http://hmap.fr/) set of music scores. Basically, a PHP script to search in a CSV.

## Document access token

Tokens are used to regulate access to scores. Nobody can access to scores without it, and it has a limit date of activation.

If the used link to the scores is used for malicious purpose, it can be quickly deactivated to stop access from the leaked link. Then a new one can be created to provide a new access to target users.

To create a new token, create a file in repository `private/tokens/` in the format `YYYYMMDD-KEY.txt`, by replacing `YYYYMMDD` by a given date (in format year-mont-day) which is the limit of activation of the token (passed this date, the token is disabled) and `KEY` by any alphanumeric string of characters (preferably randomly generated).
The file must just be created, there is nothing to add in it.

To access to the website with the token, the URL shall be: *www.mywebsite.com/?token=KEY* with *KEY* the actual `KEY` used at the end of the created file name.


## Document storage

Scores are stored in directory `private/docs`.

To add a new set of music scores here, create a new directory with the code of the score in csv (ex: "F058" for "1ere Symphonie en Ut Majeur" described in `Archives_HMAP_140126.csv`.

Then in that directory, add all scores in PDF format.

## Run in docker

This is for development purposes:
you can run the current code in docker

Build it: `sudo docker build . -t arvin2`

Run it: `sudo docker run --rm --name arvin2 --network host -it arvin2`. It
will be available on `http://localhost:1912`

Clean it (shouldn't be needed with `--rm`): `sudo docker container remove arvin2`

## Contributors

* Olivier FAURAX https://github.com/ofaurax
* Kimicol https://github.com/kimicol

## License

Copyright 2013 Olivier FAURAX

Licensed under the GNU AGPLv3: https://www.gnu.org/licenses/agpl-3.0.html
