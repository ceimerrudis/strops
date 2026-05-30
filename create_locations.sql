INSERT INTO tool_locations (name, user_id)
SELECT '', u.id
FROM users u
WHERE NOT EXISTS (
    SELECT 1 FROM tool_locations tl  
    WHERE tl.user_id = u.id
);