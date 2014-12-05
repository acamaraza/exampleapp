<?php

namespace Api\Controller;

use SebastianBergmann\Exporter\Exception;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

use Zend\Db\ResultSet\ResultSet;

class ActivityController extends AbstractRestfulController
{

    /**
     * Maps GET request to fetch single element
     *
     * @param mixed $id
     * @return JsonModel
     */
    public function get($id)
    {

        $activityObj = $this->getServiceLocator()->get('ActivityEntity');

        $result = $activityObj->fetch($id);

        if ($result instanceof ResultSet) {
            $data = $this->formatCollection($result->toArray());
        } else {
            $data = $this->formatError('Error al recuperar', 'Se ha producido un error al recuperar los datos');
        }

        return new JsonModel($data);
    }

    /**
     * Maps GET request to fetch al records
     *
     * @return JsonModel
     */
    public function getList()
    {

        $activityObj = $this->getServiceLocator()->get('ActivityEntity');
        $result = $activityObj->fetchAll();

        if ($result instanceof ResultSet) {
            $data = $this->formatCollection($result->toArray());
        } else {
            $data = $this->formatError('Error al recuperar', 'Se ha producido un error al recuperar los datos');
        }

        return new JsonModel($data);

    }

    /**
     * Maps POST request to insert record
     *
     * @param array $data
     * @return JsonModel
     */
    public function create($data)
    {

        $activityObj = $this->getServiceLocator()->get('ActivityEntity');

        // Validation
        if (empty($data) || !array_key_exists('amount', $data)) {

            $data = $this->formatError('Error al insertar', 'Parámetro suministrado no válido');

        } else {

            // Validation ok

            $id = $activityObj->insert($data);

            if (is_numeric($id)) {

                $result = $activityObj->fetch($id);

                if ($result instanceof ResultSet) {
                    $data = $this->formatCollection($result->toArray());
                } else {
                    $data = $this->formatError('Error al recuperar', 'Se ha producido un error al recuperar los datos');
                }

            } else {
                $data = $this->formatError('Error al recuperar', 'Se ha producido un error al recuperar los datos');
            }
        }

        return new JsonModel($data);

    }

    /**
     * Maps DELETE request to delete record
     *
     * @param mixed $id
     * @return JsonModel
     */
    public function delete($id)
    {

        $activityObj = $this->getServiceLocator()->get('ActivityEntity');

        // Validation
        if (preg_match("/[0-9]{1,9}/", $id)) {

            $activityObj->delete($id);
            $data = null;

        } else {

            $data = $this->formatError('Error al eliminar', 'El recurso solicitado no existe');

        }

        return new JsonModel($data);

    }

    /**
     * Maps DELETE request to all record deleting
     *
     * @return JsonModel
     */
    public function deleteList()
    {

        // Maps DELETE to delete

        return new JsonModel($this->formatError('Recurso no soportado', 'El recurso que busca no se encuentra disponible'));
    }

    /**
     * Maps PUT request to multiple record update
     *
     * @param mixed $id
     * @param array $data
     * @return JsonModel
     */
    public function update($id, $data)
    {

        return new JsonModel($this->formatError('Recurso no soportado', 'El recurso que busca no se encuentra disponible'));

    }




    // ================ Private utility methods =================

    /**
     * Formats the record collection output
     *
     * @param array $collection
     * @return array
     */

    private function formatCollection(array $collection)
    {

        // Any reformat or metatags add here

        if (count($collection) == 1) {
            // Don't return array if it is a single element
            $collection = $collection[0];
        }

        return $collection;

    }

    /**
     * Formats the error response output
     *
     * @param $title
     * @param $details
     * @return array
     */
    private function formatError($title, $details)
    {

        return array (
            'error' => array('title' => $title, 'details' => $details)
        );

    }

}

