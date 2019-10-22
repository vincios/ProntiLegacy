USE `sql119682_1`;
DROP TABLE IF EXISTS legend;
DROP TABLE IF EXISTS dbversion;

CREATE TABLE legend(
	id integer auto_increment primary key,
    creation_date date not null,
    table_name varchar(20) not null,
    legend varchar(2048)
);

INSERT INTO legend(creation_date, table_name, legend) values 
(date('2019-09-03'), 'ceramiche', '{"blue":"OLDceramicheBlue very very very very very very long line","green":"OLDceramicheGreen","red":"OLDceramicheRed","yellow":"OLDceramicheYellow"}'),
(date('2019-09-03'), 'depositi', '{"blue":"OLDdepositiBlue","green":"OLDdepositiGreen very very very very very very very very very very very very long line","red":"OLDdepositiRed","yellow":"OLDdepositiYellow"}'),
(date('2019-09-03'), 'raggruppati', '{"blue":"OLDraggruppatiBlue","green":"OLDraggruppatiGreen","red":"OLDraggruppatiRed","yellow":"OLDraggruppatiYellow"}'),
(date('2019-09-03'), 'marazzi', '{"blue":"OLDmarazziBlue","green":"OLDmarazziGreen","red":"OLDmarazziRed","yellow":"marazziYellow"}');

INSERT INTO legend(creation_date, table_name, legend) values 
(date('2019-10-03'), 'ceramiche', '{"blue":"ceramicheBlue","green":"ceramicheGreen","red":"ceramicheRed","yellow":"ceramicheYellow"}'),
(date('2019-10-03'), 'depositi', '{"blue":"depositiBlue","green":"depositiGreen","red":"depositiRed","yellow":"depositiYellow"}'),
(date('2019-10-03'), 'raggruppati', '{"blue":"raggruppatiBlue","green":"raggruppatiGreen","red":"raggruppatiRed","yellow":"raggruppatiYellow"}'),
(date('2019-10-03'), 'marazzi', '{"blue":"marazziBlue","green":"marazziGreen","red":"marazziRed","yellow":"marazziYellow"}');


CREATE TABLE dbversion ( version int not null primary key );
INSERT INTO dbversion(version) values (2);