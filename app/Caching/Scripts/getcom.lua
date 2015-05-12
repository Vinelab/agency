-- turn an HGETALL call from a Redis hash into a native dictionary
local hgetall = function (key)
  local bulk = redis.call('HGETALL', key)
    local result = {}
    local nextkey
    for i, v in ipairs(bulk) do
        if i % 2 == 1 then
            nextkey = v
        else
            result[nextkey] = v
        end
    end
    return result
end

-- get the comments ids for the given content
local comments_ids = redis.call('ZREVRANGE', KEYS[2], ARGV[1], ARGV[2])

rawset(_G, "me", ARGV[3])
rawset(_G, "comments", {})
rawset(_G, "prefix", KEYS[1])

for index, id in ipairs(comments_ids) do
    local cache_key = rawget(_G, "prefix").."comments:"..id
    local comment = hgetall(cache_key)
    local user = hgetall(rawget(_G, "prefix").."users:"..comment.user)
    comment.user = user

    comment.me = {}
    comment.me.liked = redis.call("SISMEMBER", cache_key..":likes", rawget(_G, "me"))
    comment.me.reported = redis.call("SISMEMBER", cache_key..":reports", rawget(_G, "me"))

    comment.likes = redis.call("SCARD", cache_key..":likes")
    comment.reports = redis.call("SCARD", cache_key..":reports")

    rawget(_G, 'comments')[index] = cjson.encode(comment)
end

return rawget(_G, 'comments')
