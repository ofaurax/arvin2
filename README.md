# arvin2

A script to search in HMAP (http://hmap.fr/) set of music scores. Basically, a PHP script to search in a CSV.

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
