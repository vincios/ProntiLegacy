SELECT * FROM (
SELECT * FROM pronticeramiche 
JOIN ceramica 
WHERE ceramica.idgruppo!=0 and ceramica.nome=pronticeramiche.ceramica and eliminato=0 and date(pronticeramiche.data_aggiunta) <= '2018-09-13' 
UNION
SELECT * FROM pronticeramiche 
JOIN ceramica 
WHERE ceramica.idgruppo!=0 and ceramica.nome=pronticeramiche.ceramica and eliminato=1 and ('2018-09-13' between date(data_aggiunta) and data_eliminazione) 
) AS T1
ORDER by idgruppo,ceramica,cliente