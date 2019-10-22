USE `sql119682_1`;
ALTER TABLE pronticeramiche ADD COLUMN palette VARCHAR(50) AFTER quintali;
ALTER TABLE prontidepositi ADD COLUMN palette VARCHAR(50) AFTER quintali;
ALTER TABLE prontimarazzi ADD COLUMN palette VARCHAR(50) AFTER quintali;
ALTER TABLE prontiraggruppati ADD COLUMN palette VARCHAR(50) AFTER quintali;

UPDATE dbversion SET version=3 WHERE version=2;