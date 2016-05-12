<?php

namespace Harvest;

use DateTime;

class Entry
{
    /**
     * Timer start date time.
     *
     * @var string
     */
    protected $timerStartedAt;

    /**
     * Project id.
     *
     * @var int
     */
    protected $projectId;

    /**
     * Project name.
     *
     * @var string
     */
    protected $project;

    /**
     * Date the time was spent.
     *
     * @var string
     */
    protected $spentAt;

    /**
     * Task id.
     *
     * @var string
     */
    protected $taskId;

    /**
     * Task name.
     *
     * @var string
     */
    protected $task;

    /**
     * Client name.
     *
     * @var string
     */
    protected $client;

    /**
     * Entty id.
     *
     * @var int
     */
    protected $id;

    /**
     * Notes.
     *
     * @var string
     */
    protected $notes;

    /**
     * Created datetime.
     *
     * @var string
     */
    protected $createdAt;

    /**
     * Updated datetime.
     *
     * @var string
     */
    protected $updatedAt;

    /**
     * Decimal hours without timer.
     *
     * @var float
     */
    protected $hoursWithoutTimer;

    /**
     * Decimal hours.
     *
     * @var float
     */
    protected $hours;

    public function __construct(array $data)
    {
        if (isset($data['timer_started_at'])) {
            $this->timerStartedAt = DateTime::createFromFormat(DateTime::ISO8601, $data['timer_started_at']);
        }

        $this->projectId         = $data['project_id'] ?? 0;
        $this->project           = $data['project'] ?? '';
        $this->userId            = $data['user_id'] ?? 0;
        $this->spentAt           = DateTime::createFromFormat('Y-m-d', $data['spent_at']);
        if (!$this->spentAt) {
            throw new \Exception('Fail: ' . json_encode($data));
        }
        $this->taskId            = $data['task_id'] ?? 0;
        $this->task              = $data['task'] ?? '';
        $this->client            = $data['client'] ?? '';
        $this->id                = $data['id'] ?? 0;
        $this->notes             = $data['notes'] ?? '';
        $this->createdAt         = DateTime::createFromFormat(DateTime::ISO8601, $data['created_at']);
        $this->updatedAt         = DateTime::createFromFormat(DateTime::ISO8601, $data['updated_at']);
        $this->hoursWithoutTimer = $data['hours_without_timer'] ?? 0;
        $this->hours             = $data['hours'] ?? 0;
    }

    /**
     * Gets the Timer start datetime.
     *
     * @return DateTime
     */
    public function getTimerStartedAt()
    {
        return $this->timerStartedAt;
    }

    /**
     * Gets the Timer start datetime as a string
     *
     * @return string
     */
    public function getTimerStartedAtString()
    {
        return $this->timerStartedAt ? $this->timerStartedAt->format(DateTime::ISO8601) : null;
    }

    /**
     * Gets the Project id.
     *
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Gets the Project name.
     *
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Gets the Date the time was spent.
     *
     * @return DateTime
     */
    public function getSpentAt()
    {
        return $this->spentAt;
    }

    /**
     * Gets the Task id.
     *
     * @return string
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Gets the Task name.
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Gets the Client name.
     *
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Gets the Entty id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the Notes.
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Gets the Created datetime.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets the created datetime as a string.
     *
     * @return string|null
     */
    public function getCreatedAtString()
    {
        return $this->createdAt ? $this->createdAt->format(DateTime::ISO8601) : null;
    }

    /**
     * Gets the Updated datetime.
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Gets the updated datetime as a string.
     *
     * @return string|null
     */
    public function getUpdatedAtString()
    {
        return $this->updatedAt ? $this->updatedAt->format(DateTime::ISO8601) : null;
    }

    /**
     * Gets the Decimal hours without timer.
     *
     * @return float
     */
    public function getHoursWithoutTimer()
    {
        return $this->hoursWithoutTimer;
    }

    /**
     * Gets the Decimal hours.
     *
     * @return float
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Get the hours as a human-readable time.
     *
     * @return string
     */
    public function getTime()
    {
        $decimalHours = $this->getHours();

        $hours = floor($decimalHours);
        $minutes = 60 * ($decimalHours - $hours);

        return sprintf(
            '%d:%02d',
            $hours,
            $minutes
        );
    }
}
