# Mysql to Clickhouse Bridge

### Aplikacja umożliwiająca kopiowanie danych pomiędzy baza mysql oraz clickhouse.

#### Instalacja
    docker-compose build
#### Uruchomienie aplikacji
    docker-compose up -d
    
##### Aplikacja powinna być dostępna pod adresem http://localhost

#### Wejście do kontenera
    docker-compose exec <nazwa kontenera> bash
    np docker-compose exec php-fpm bash
    
#### Wgranie bazy danych:
    docker-compose exec mariadb bash
    mysql -u ips2019 -p ips2019 < dbdump/createDatabase.sql
    lub mysql -u ips2019 -p ips2019 < dbdump/one_million_database.sql
    haslo: ips2019

### Terminarz
  - opracowanie założeń i wymagań do: 28.10.2019
  - opracowanie wstępnej dokumentacji do: 04.11.2019
  - implementacja do: 25.11.2019
  - testy do: 02.12.2019
  - przygotowanie dokumentacji do: 09.12.2019
  - prezentacja projektu, rozliczenie, zaliczenia: 16.12.2019 
  
### Dokumentacja

Diagram komponentów
![diagram komponentów](documentation/uml/components.png)