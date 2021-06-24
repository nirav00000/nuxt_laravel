# Apricot

Apricot is a platform to manage all hiring process at single place.

## Installation

Make sure given things are installed in your machines.

:heavy_check_mark: Make sure `Postgres v12.x.x` installed on your machine.

:heavy_check_mark: Make sure `PHP v7.4.x` installed on your machine.

:heavy_check_mark: Make sure `Composer v2.x.x` installed on your machine.

:heavy_check_mark: Make sure `Laravel v6.x.x` installed on your machine.

:heavy_check_mark: Make sure `Node v14.x.x` installed on your machine.

:heavy_check_mark: Make sure `NPM v6.x.x` installed on your machine.

## Folder Structure

Make sure you have cloned this repository.

- `web` - Front end of apricot, that made using `NuxtJS` and `Boostrap`

- `app` - Back end of apricot, that made using `Laravel`


## Containerization

Make sure you have `docker` and `docker-compose` installed on your machine.

- We used `DexIdp` for LDAP authentication,  and `Google` for client authentication, so make sure you have added `API_KEY` and `API_SECRET` in `ldap-oauth.cfg` and `oauth.cfg`.

```bash
# Start project in staging mode
$> docker-compose up
# Open 127.0.0.1:5124 in your browser
```
## Useful links

Useful links that are used during project development.

**Stagging**: `https://apricot.improwised.dev`