<?php
namespace Walltwisters\interfacesrepo;

interface IRepositoryFactory {
    public function getRepository($repositoryName);
}
