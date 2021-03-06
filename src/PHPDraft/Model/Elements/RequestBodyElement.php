<?php
/**
 * This file contains the RequestBodyElement.
 *
 * @package PHPDraft\Model\Elements
 *
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */

namespace PHPDraft\Model\Elements;

/**
 * Class RequestBodyElement.
 */
class RequestBodyElement extends ObjectStructureElement
{
    /**
     * Print the request body as a string.
     *
     * @param string $type The type of request
     *
     * @return string Request body
     */
    public function print_request($type = 'application/x-www-form-urlencoded')
    {
        if (is_array($this->value)) {
            $return = '<code class="request-body">';
            $list   = [];
            foreach ($this->value as $object) {
                if (get_class($object) === self::class) {
                    $list[] = $object->print_request($type);
                }
            }

            switch ($type) {
                case 'application/x-www-form-urlencoded':
                    $return .= join('&', $list);
                    break;
                default:
                    $return .= join(PHP_EOL, $list);
                    break;
            }

            $return .= '</code>';

            return $return;
        }

        $value = (empty($this->value)) ? '?' : $this->value;

        switch ($type) {
            case 'application/x-www-form-urlencoded':
                return $this->key . '=<span>' . $value . '</span>';
                break;
            default:
                $object             = [];
                $object[$this->key] = $value;

                return json_encode($object);
                break;
        }
    }

    /**
     * Return a new instance.
     *
     * @return RequestBodyElement
     */
    protected function new_instance()
    {
        return new self();
    }
}
