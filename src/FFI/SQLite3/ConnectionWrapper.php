<?php
namespace Moxio\SQLiteExtendedAPI\FFI\SQLite3;

use Moxio\SQLiteExtendedAPI\WrappedConnection;

class ConnectionWrapper {
    private \FFI $sqlite3_ffi;

    public function __construct() {
        try {
            $this->sqlite3_ffi = \FFI::cdef(file_get_contents(__DIR__ . "/sqlite3.h"), "sqlite3.so");
        } catch (\FFI\Exception $e) {
            // Fallback for when the sqlite3.so extension is not available.
            // See https://github.com/Moxio/sqlite-extended-api/issues/7
            $this->sqlite3_ffi = \FFI::cdef(file_get_contents(__DIR__ . "/sqlite3.h"), "libsqlite3.so");
        }
    }

    public function wrapConnection(\FFI\CData $sqlite3_void_pointer): WrappedConnection {
        $sqlite3_pointer = $this->sqlite3_ffi->cast("struct sqlite3*", $sqlite3_void_pointer);
        return new WrappedConnection($this->sqlite3_ffi, $sqlite3_pointer);
    }
}
