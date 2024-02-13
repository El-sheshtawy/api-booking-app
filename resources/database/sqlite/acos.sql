SELECT load_extension('mod_spatialite');

CREATE TEMPORARY TABLE IF NOT EXISTS spatialite_aux (
    geometry geometry,
    dist_column real
);

CREATE FUNCTION IF NOT EXISTS acos(double) RETURNS double
BEGIN
    DECLARE acos_result double;
SELECT ACOS(double) INTO acos_result FROM spatialite_aux WHERE ROWID = 1;
RETURN acos_result;
END;
