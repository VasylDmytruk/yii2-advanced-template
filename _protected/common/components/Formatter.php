<?php

namespace common\components;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;

/**
 * Class Formatter Adds properties to get raw date and datetime formats. Possible additional functional in future.
 *
 * @property string $rawDatetimeFormat Raw datetime format, removes `[[Formatter::formatPrefix]]` from [[Formatter::datetimeFormat]]
 * @property string $rawDateFormat Raw date format, removes `[[Formatter::formatPrefix]]` from [[Formatter::dateFormat]]
 */
class Formatter extends \yii\i18n\Formatter
{
    /**
     * Value to be use to convert milliseconds to seconds.
     */
    const MILLISECONDS = 1000;

    /**
     * @var string Prefix to be use to remove from [[Formatter::datetimeFormat]] and [[Formatter::dateFormat]].
     * to get raw values.
     */
    public $formatPrefix = 'php:';
    /**
     * @var string Datetime with microseconds delimiter
     */
    public $datetimeWithMicrosecondsDelimiter = ' ';


    /**
     * Gets raw datetime format, removes `[[Formatter::formatPrefix]]` from [[Formatter::datetimeFormat]].
     *
     * @return string
     */
    public function getRawDatetimeFormat()
    {
        $rawFormat = str_replace($this->formatPrefix, '', $this->datetimeFormat);

        return $rawFormat;
    }

    /**
     * Gets raw date format, removes `[[Formatter::formatPrefix]]` from [[Formatter::dateFormat]].
     *
     * @return string
     */
    public function getRawDateFormat()
    {
        $rawFormat = str_replace($this->formatPrefix, '', $this->dateFormat);

        return $rawFormat;
    }

    /**
     * Formats a date, time or datetime in a float number as UNIX timestamp with milliseconds.
     * @param int|string|\DateTime $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
     *
     * @return string the formatted result.
     */
    public function asMillisecondTimestamp($value)
    {
        $millisecondTimestamp = Yii::$app->formatter->asTimestamp($value) * self::MILLISECONDS;

        return $millisecondTimestamp;
    }

    /**
     * Formats the value as a datetime with milliseconds.
     * @param int|string|\DateTime $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp. A UNIX timestamp is always in UTC by its definition.
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object. You may set the time zone
     *   for the DateTime object to specify the source time zone.
     *
     * The formatter will convert date values according to [[timeZone]] before formatting it.
     * If no timezone conversion should be performed, you need to set [[defaultTimeZone]] and [[timeZone]] to the same value.
     *
     * @param string $format the format used to convert the value into a date string.
     * If null, [[datetimeFormat]] will be used.
     *
     * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
     * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
     *
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/en/function.date.php)-function.
     *
     * @return string the formatted result.
     * @throws InvalidArgumentException if the input value can not be evaluated as a date value.
     * @throws InvalidConfigException if the date format is invalid.
     * @see datetimeFormat
     */
    public function asDatetimeWithMilliseconds($value, $format = null)
    {
        $millisecondsTime = $this->getMillisecondsWithZeroTime($value);

        $secondsTime = (int)($value / self::MILLISECONDS);

        $formattedDateTime = Yii::$app->formatter->asDatetime($secondsTime, $format) .
            $this->datetimeWithMicrosecondsDelimiter . $millisecondsTime;

        return $formattedDateTime;
    }

    /**
     * Transforms float milliseconds time in string milliseconds time with zeros:
     *
     * ```
     * 1523534316.88 => '880'
     * 1523534316.8 => '800'
     * 1523534316.0 => '000'
     * ```
     *
     * @param float $value
     *
     * @return string
     */
    protected function getMillisecondsWithZeroTime($value)
    {
        $millisecondsWithZeroTime = (string)($value % self::MILLISECONDS);

        if (strlen($millisecondsWithZeroTime) < 2) {
            $millisecondsWithZeroTime = '00' . $millisecondsWithZeroTime;
        } elseif (strlen($millisecondsWithZeroTime) < 3) {
            $millisecondsWithZeroTime = '0' . $millisecondsWithZeroTime;
        }

        return $millisecondsWithZeroTime;
    }
}
