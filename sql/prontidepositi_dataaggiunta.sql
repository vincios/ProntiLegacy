alter table prontidepositi add column data_aggiunta datetime after selezionato;
alter table prontidepositi modify column data_aggiunta datetime default current_timestamp;

update prontidepositi set data_aggiunta = current_timestamp where eliminato = 0;