<?php

namespace QueueAPI;

class QueueAPI
{
    private static array $queues = [];
    private static int $maxQueueSize = 100;

    public static function setMaxQueueSize(int $max): void
    {
        self::$maxQueueSize = $max;
    }

    public static function add(string $playerName, string $queueName): bool
    {
        if (!isset(self::$queues[$queueName])) {
            self::$queues[$queueName] = [];
        }

        if (isset(self::$queues[$queueName][$playerName])) {
            return false;
        }

        if (count(self::$queues[$queueName]) >= self::$maxQueueSize) {
            return false;
        }

        $position = count(self::$queues[$queueName]) + 1;
        self::$queues[$queueName][$playerName] = $position;

        return true;
    }

    public static function remove(string $playerName, string $queueName): bool
    {
        if (!isset(self::$queues[$queueName][$playerName])) {
            return false;
        }

        $removedPos = self::$queues[$queueName][$playerName];
        unset(self::$queues[$queueName][$playerName]);

        foreach (self::$queues[$queueName] as $name => $pos) {
            if ($pos > $removedPos) {
                self::$queues[$queueName][$name] = $pos - 1;
            }
        }

        return true;
    }

    public static function getPosition(string $playerName, string $queueName): int
    {
        return self::$queues[$queueName][$playerName] ?? 0;
    }

    public static function next(string $queueName): ?string
    {
        if (!isset(self::$queues[$queueName]) || empty(self::$queues[$queueName])) {
            return null;
        }

        foreach (self::$queues[$queueName] as $name => $pos) {
            if ($pos === 1) {
                self::remove($name, $queueName);
                return $name;
            }
        }

        return null;
    }

    public static function size(string $queueName): int
    {
        return isset(self::$queues[$queueName]) ? count(self::$queues[$queueName]) : 0;
    }

    public static function getPlayers(string $queueName): array
    {
        if (!isset(self::$queues[$queueName])) {
            return [];
        }

        $queue = self::$queues[$queueName];
        asort($queue);

        return array_keys($queue);
    }

    public static function getQueues(): array
    {
        return array_keys(self::$queues);
    }
}