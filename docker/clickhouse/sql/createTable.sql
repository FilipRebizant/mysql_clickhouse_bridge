
CREATE TABLE MainTable (
id Int64,
Name String,
Age String,
City String

)
ENGINE = MergeTree() PARTITION BY id PRIMARY KEY id ORDER BY id
