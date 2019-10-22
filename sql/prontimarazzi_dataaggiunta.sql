#alter table prontimarazzi drop column data_aggiunta;

alter table prontimarazzi add column data_aggiunta datetime after selezionato;
alter table prontimarazzi modify column data_aggiunta datetime default current_timestamp;

update prontimarazzi set data_aggiunta = current_timestamp where eliminato = 0;

SELECT * FROM sql119682_1.prontimarazzi;