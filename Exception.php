<?php
/**
 * This file is part of the Borobudur package.
 *
 * (c) 2017 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Borobudur\Component\Exception;

use Exception as BaseException;
use Throwable;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Exception extends BaseException
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @var array
     */
    protected $params;

    public function __construct(string $format, array $params = null, int $code = 0, Throwable $previous = null)
    {
        $this->format = $format;
        $this->params = $params;

        parent::__construct($this->format($format), $code, $previous);
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * Transform params.
     *
     * @param array $params
     *
     * @return array
     */
    public function transformParams(array $params): array
    {
        $newParams = [];
        foreach ($params as $key => $value) {
            $newParams['%' . $key . '%'] = $value;
        }

        return $newParams;
    }

    /**
     * Get formatted message.
     *
     * @param string $message
     *
     * @return string
     */
    public function format(string $message): string
    {
        if (null === $this->params) {
            return $message;
        }

        foreach ($this->transformParams($this->params) as $key => $value) {
            $message = str_replace($key, $value, $message);
        }

        return $message;
    }
}
