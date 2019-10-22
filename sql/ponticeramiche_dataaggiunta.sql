#alter table pronticeramiche drop column data_aggiunta;

alter table pronticeramiche add column data_aggiunta datetime after selezionato;
alter table pronticeramiche modify column data_aggiunta datetime default current_timestamp;

update pronticeramiche set data_aggiunta = current_timestamp where eliminato = 0;

#insert into pronticeramiche(Ceramica, cliente, quintali, note, selezionato, eliminato, data_eliminazione) values('TECNOKOLLA', 'CARBONE', '', '', '0', '0', '0000-00-00');

select * from pronticeramiche;