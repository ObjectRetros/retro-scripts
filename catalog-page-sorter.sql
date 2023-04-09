SET @orderNum = 0;

UPDATE catalog_pages
SET order_num = (@orderNum := @orderNum + 1) - 1
WHERE parent_id NOT IN (
    -- Your catalog page IDs to exclude from the sorting. Eg. catalog tabs etc. separate them by commas. - Eg. 1,2,3,4,5,6
)
ORDER BY caption;
