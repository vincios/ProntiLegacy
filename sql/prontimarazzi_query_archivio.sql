SELECT * FROM (
	SELECT pm.id,pm.Deposito,pm.Cliente,pm.quintali,pm.dds,pm.note,m.indirizzo,pm.selezionato, pm.data_aggiunta, pm.eliminato, pm.data_eliminazione, m.colore 
	FROM prontimarazzi pm JOIN marazzi m
	WHERE pm.deposito=m.nome and pm.eliminato=0 and date(pm.data_aggiunta) <= '2018-09-20'
    UNION
    SELECT pm.id,pm.Deposito,pm.Cliente,pm.quintali,pm.dds,pm.note,m.indirizzo,pm.selezionato, pm.data_aggiunta, pm.eliminato, pm.data_eliminazione, m.colore 
	FROM prontimarazzi pm JOIN marazzi m
	WHERE pm.deposito=m.nome and pm.eliminato=1 and ('2018-09-20' between date(pm.data_aggiunta) and pm.data_eliminazione)
) AS T1
ORDER by deposito,cliente,dds
