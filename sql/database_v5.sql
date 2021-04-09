USE `sql119682_1`;
ALTER TABLE pronticeramiche add column autista varchar(200) after `cliente`;
ALTER TABLE prontidepositi add column autista varchar(200) after `cliente`;
ALTER TABLE prontimarazzi add column autista varchar(200) after `cliente`;
ALTER TABLE prontiraggruppati add column autista varchar(200) after `cliente`;

UPDATE dbversion SET version=5 WHERE version=4;