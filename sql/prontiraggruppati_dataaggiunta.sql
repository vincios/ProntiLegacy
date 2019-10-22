alter table prontiraggruppati add column data_aggiunta datetime after selezionato;
alter table prontiraggruppati modify column data_aggiunta datetime default current_timestamp;

update prontiraggruppati set data_aggiunta = current_timestamp where eliminato = 0;

SELECT * FROM sql119682_1.prontiraggruppati;