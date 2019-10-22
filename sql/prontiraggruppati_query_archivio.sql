SELECT * FROM (
	SELECT * FROM prontiraggruppati pr JOIN ceramica c
	WHERE c.idgruppo!=0 and c.nome=pr.ceramica and eliminato=0 and date(pr.data_aggiunta) <= '2018-09-21'
	UNION 
	SELECT * FROM prontiraggruppati pr JOIN ceramica c
	WHERE c.idgruppo!=0 and c.nome=pr.ceramica and eliminato=1 and ('2018-09-21' between date(pr.data_aggiunta) and pr.data_eliminazione)
) AS T1
ORDER by idgruppo,ceramica,materiale,cliente