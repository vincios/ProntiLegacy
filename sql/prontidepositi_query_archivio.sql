SELECT *
 FROM (
	SELECT pd.id,pd.Deposito,pd.Ceramica,pd.Cliente,pd.quintali,pd.note,d.indirizzo,pd.selezionato, pd.data_aggiunta, pd.eliminato, pd.data_eliminazione, d.colore 
	FROM prontidepositi pd JOIN depositi d
	WHERE pd.deposito=d.nome and pd.eliminato=0 and date(pd.data_aggiunta) <= '2018-09-16'
	UNION
	SELECT pd.id,pd.Deposito,pd.Ceramica,pd.Cliente,pd.quintali,pd.note,d.indirizzo,pd.selezionato, pd.data_aggiunta, pd.eliminato, pd.data_eliminazione, d.colore 
	FROM prontidepositi pd JOIN depositi d
	WHERE pd.deposito=d.nome and pd.eliminato=1 and ('2018-09-16' between date(pd.data_aggiunta) and pd.data_eliminazione)
) AS T1
ORDER by deposito,ceramica,cliente