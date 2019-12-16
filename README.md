# MySQL to ClickHouse Bridge

### Aplikacja umożliwiająca kopiowanie danych pomiędzy bazami MariaDB i ClickHouse.

### Autorzy
- Filip Rebizant
- Konrad Rejman
- Mateusz Matyaszek
- Bartłomiej Kudełka

## Spis treści
* [Opis aplikacji](#Opis-aplikacji)
* [Instalacja i uruchmienie aplikacji](#Instalacja)
* [Schematy UML](#schematy-uml)
* [Wykorzystane technologie](#wykorzystane-technologie)
* [Aplikacja](#Aplikacja)

### Opis aplikacji
Celem aplikacji jest tworzenie kopii danych pomiędzy systemami bazodanowymi MariaDB i ClickHouse. Aplikacja przetwarza dane o sporych rozmiarach, typu big-data.

### Instalacja
    docker-compose build
#### Uruchomienie aplikacji
    docker-compose up -d
    
##### Aplikacja powinna być dostępna pod adresem http://localhost:8080

#### Wejście do kontenera
    docker-compose exec <nazwa kontenera> bash
    np docker-compose exec php-fpm bash
    
#### Wgranie bazy danych:
    docker-compose exec mariadb bash
    mysql -u ips2019 -p ips2019 < dbdump/createDatabase.sql
    lub mysql -u ips2019 -p ips2019 < sql/one_million_database.sql
    haslo: ips2019
  
### Schematy UML
- Diagram komponentów
![diagram komponentów](documentation/uml/components.png)

### Wykorzystane technologie
- MariaDB
- ClickHouse
- Symfony
- Doctrine DBAL ClickHouse Driver
- NginX

### Aplikacja
- Zakładka MariaDB
![1](documentation/uml/1.png)
![2](documentation/uml/2.png)
![3](documentation/uml/3.png)
- Edycja danych MariaDB
![4](documentation/uml/4.png)
![5](documentation/uml/5.png)
- Usuwanie rekordu
![6](documentation/uml/6.png)
- Kopiowanie
![7](documentation/uml/7.png)
![8](documentation/uml/8.png)

- ClickHouse
![11](documentation/uml/10.png)
![10](documentation/uml/11.png)
### Terminarz
  - opracowanie założeń i wymagań do: 28.10.2019
  - opracowanie wstępnej dokumentacji do: 04.11.2019
  - implementacja do: 25.11.2019
  - testy do: 02.12.2019
  - przygotowanie dokumentacji do: 09.12.2019
  - prezentacja projektu, rozliczenie, zaliczenia: 16.12.2019 
