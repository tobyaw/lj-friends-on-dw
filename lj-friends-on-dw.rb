#!/usr/bin/ruby

require 'open-uri'

friends = Array.new

ARGV.each do |lj_user|
  open("http://www.livejournal.com/misc/fdata.bml?user=#{lj_user}").readlines.each do |line|
    friends.push line.slice(2...line.length).strip if line =~ /^<|>/
  end
end

friends.sort.uniq.each do |friend|
  puts friend if open("http://users.dreamwidth.org/#{friend}").read !~ /^<h1>Unknown User/
end

