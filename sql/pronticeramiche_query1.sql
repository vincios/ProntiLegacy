SELECT * FROM (select * from pronticeramiche pc
              JOIN ceramica c
              WHERE c.idgruppo=0 and c.nome=pc.ceramica and pc.eliminato=0 and date(pc.data_aggiunta) <= '2018-09-13'
              union
              select * from pronticeramiche pc
              JOIN ceramica c
              WHERE c.idgruppo=0 and c.nome=pc.ceramica and pc.eliminato=1 and ('2018-09-13' between date(data_aggiunta) and data_eliminazione)
              ) as T1
		ORDER by ceramica,cliente