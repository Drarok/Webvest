<?php

namespace Harvest;

class Entry implements \Serializable
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

    public function __construct(array $data = null)
    {
        if ($data) {
            $this->fromArray($data);
        }
    }

    public function serialize()
    {
        return serialize($this->toArray());
    }

    public function toArray()
    {
        return array(
            'timer_started_at'    => $this->timerStartedAt ? $this->timerStartedAt->format(\DateTime::ISO8601) : null,
            'project_id'          => $this->projectId,
            'project'             => $this->project,
            'user_id'             => $this->userId,
            'spent_at'            => $this->spentAt ? $this->spentAt->format(\DateTime::ISO8601) : null,
            'task_id'             => $this->taskId,
            'task'                => $this->task,
            'client'              => $this->client,
            'id'                  => $this->id,
            'notes'               => $this->notes,
            'created_at'          => $this->createdAt->format(\DateTime::ISO8601),
            'updated_at'          => $this->updatedAt->format(\DateTime::ISO8601),
            'hours_without_timer' => $this->hoursWithoutTimer,
            'hours'               => $this->hours,
        );
    }

    public function unserialize($data)
    {
        $data = unserialize($data);
        $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        if (array_key_exists('timer_started_at', $data)) {
            $this->timerStartedAt    = \DateTime::createFromFormat(\DateTime::ISO8601, $data['timer_started_at']);
        }
        $this->projectId         = $data['project_id'];
        $this->project           = $data['project'];
        $this->userId            = $data['user_id'];
        $this->spentAt           = \DateTime::createFromFormat(\DateTime::ISO8601, $data['spent_at']);
        $this->taskId            = $data['task_id'];
        $this->task              = $data['task'];
        $this->client            = $data['client'];
        $this->id                = $data['id'];
        $this->notes             = $data['notes'];
        $this->createdAt         = \DateTime::createFromFormat(\DateTime::ISO8601, $data['created_at']);
        $this->updatedAt         = \DateTime::createFromFormat(\DateTime::ISO8601, $data['updated_at']);
        $this->hoursWithoutTimer = $data['hours_without_timer'];
        $this->hours             = $data['hours'];
    }

    /**
     * Gets the Timer start datetime.
     *
     * @return \DateTime
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
        return $this->timerStartedAt ? $this->timerStartedAt->format(\DateTime::ISO8601) : null;
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
     * @return \DateTime
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
     * @return \DateTime
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
        return $this->createdAt ? $this->createdAt->format(\DateTime::ISO8601) : null;
    }

    /**
     * Gets the Updated datetime.
     *
     * @return \DateTime
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
        return $this->updatedAt ? $this->updatedAt->format(\DateTime::ISO8601) : null;
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
