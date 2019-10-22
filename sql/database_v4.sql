USE `sql119682_1`;
ALTER TABLE ceramica ADD COLUMN note VARCHAR(2000) AFTER telefono;
ALTER TABLE depositi ADD COLUMN note VARCHAR(2000) AFTER telefono;
ALTER TABLE marazzi ADD COLUMN note VARCHAR(2000) AFTER telefono;

UPDATE dbversion SET version=4 WHERE version=3;