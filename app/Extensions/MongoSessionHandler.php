<?php


namespace App\Extensions;


class MongoSessionHandler implements \SessionHandlerInterface
{

    /**
     * The close method, like the open method, can also usually be disregarded.
     * For most drivers, it is not needed.
     * @inheritDoc
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * The destroy method should remove the data associated with the $sessionId from persistent storage.
     * @inheritDoc
     */
    public function destroy($session_id)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * The gc method should destroy all session data that is older than the given $lifetime, which is a UNIX timestamp.
     * For self-expiring systems like Memcached and Redis, this method may be left empty.
     * @inheritDoc
     */
    public function gc($maxlifetime)
    {
        // TODO: Implement gc() method.
    }

    /**
     * The open method would typically be used in file based session store systems.
     * Since Laravel ships with a file session driver, you will almost never need to put anything in this method.
     * You can leave it as an empty stub.
     * It is a fact of poor interface design (which we'll discuss later) that PHP requires us to implement this method.
     * @inheritDoc
     */
    public function open($save_path, $name)
    {
        // TODO: Implement open() method.
    }

    /**
     * The read method should return the string version of the session data associated with the given $sessionId.
     * There is no need to do any serialization or other encoding when retrieving or storing session data in your driver,
     * as Laravel will perform the serialization for you.
     * @inheritDoc
     */
    public function read($session_id)
    {
        // TODO: Implement read() method.
    }

    /**
     * The write method should write the given $data string associated with the $sessionId to some persistent storage system,
     * such as MongoDB, Dynamo, etc.
     * Again, you should not perform any serialization - Laravel will have already handled that for you.
     * @inheritDoc
     */
    public function write($session_id, $session_data)
    {
        // TODO: Implement write() method.
    }
}
